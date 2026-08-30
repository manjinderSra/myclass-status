<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Rule;
use App\Models\RuleCategory;
use Illuminate\Support\Facades\Auth;

class SchoolRulesController extends Controller
{
    /**
     * Display the rules and regulations page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the school associated with the admin user
        $school = School::where('admin_id', $user->id)->first();
        // dd($school);
        // Get all rule categories for the school
        $categories = RuleCategory::where('school_id', $school->id)->get();
        // dd($categories);
        // Get all rules for the school with their categories
        $rules = Rule::with('category')
            ->where('school_id', $school->id)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($rules);
        return view('client.schoolPanel.generalSettings.rulesAndRegulations', [
            'school' => $school,
            'categories' => $categories,
            'rules' => $rules
        ]);
    }
    
    /**
     * Store a new rule category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCategory(Request $request)
    {
        try {
            // Log request for debugging
            \Log::info('Store Category Request:', $request->all());
            
            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
            ]);
            
            // Get the authenticated user's school
            $user = Auth::user();
            $school = School::where('admin_id', $user->id)->first();
            
            if (!$school) {
                \Log::error('School not found for user', ['user_id' => $user->id]);
                return redirect()->back()->with('error', 'School not found for the current user.');
            }
            
            // Create the new category
            $category = new RuleCategory();
            $category->school_id = $school->id;
            $category->name = $validated['name'];
            $category->description = $validated['description'] ?? null;
            $category->save();
            
            \Log::info('Category created:', $category->toArray());
            
            return redirect()->back()->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating category: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'An error occurred while creating the category: ' . $e->getMessage());
        }
    }
    
    /**
     * Store a new rule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRule(Request $request)
    {
        try {
            // Log request for debugging
            \Log::info('Store Rule Request:', $request->all());
            
            // Validate the request data
            $validated = $request->validate([
                'rule_category_id' => 'required|exists:rule_categories,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
            ]);
            
            // Get the authenticated user's school
            $user = Auth::user();
            $school = School::where('admin_id', $user->id)->first();
            
            if (!$school) {
                \Log::error('School not found for user', ['user_id' => $user->id]);
                return redirect()->back()->with('error', 'School not found for the current user.');
            }
            
            // Verify the category belongs to this school
            $category = RuleCategory::where('id', $validated['rule_category_id'])
                ->where('school_id', $school->id)
                ->first();
                
            if (!$category) {
                \Log::warning('Invalid category selected', [
                    'category_id' => $validated['rule_category_id'],
                    'school_id' => $school->id
                ]);
                
                return redirect()->back()->with('error', 'Invalid category selected.');
            }
            
            // Create the new rule
            $rule = new Rule();
            $rule->school_id = $school->id;
            $rule->rule_category_id = $validated['rule_category_id'];
            $rule->title = $validated['title'];
            $rule->description = $validated['description'];
            $rule->is_active = true;
            $rule->save();
            
            \Log::info('Rule created:', $rule->toArray());
            
            return redirect()->back()->with('success', 'Rule created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating rule: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'An error occurred while creating the rule: ' . $e->getMessage());
        }
    }
    
    /**
     * Update an existing rule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRule(Request $request, $id)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'rule_category_id' => 'required|exists:rule_categories,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
            ]);
            
            // Get the authenticated user's school
            $user = Auth::user();
            $school = School::where('admin_id', $user->id)->first();
            
            // Verify the rule belongs to this school
            $rule = Rule::where('id', $id)
                ->where('school_id', $school->id)
                ->first();
                
            if (!$rule) {
                return redirect()->back()->with('error', 'Rule not found or access denied.');
            }
            
            // Verify the category belongs to this school
            $category = RuleCategory::where('id', $validated['rule_category_id'])
                ->where('school_id', $school->id)
                ->first();
                
            if (!$category) {
                return redirect()->back()->with('error', 'Invalid category selected.');
            }
            
            // Update the rule
            $rule->rule_category_id = $validated['rule_category_id'];
            $rule->title = $validated['title'];
            $rule->description = $validated['description'];
            $rule->save();
            
            return redirect()->back()->with('success', 'Rule updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating rule: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'An error occurred while updating the rule: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete a rule.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteRule($id)
    {
        try {
            // Get the authenticated user's school
            $user = Auth::user();
            $school = School::where('admin_id', $user->id)->first();
            
            // Verify the rule belongs to this school
            $rule = Rule::where('id', $id)
                ->where('school_id', $school->id)
                ->first();
                
            if (!$rule) {
                return redirect()->back()->with('error', 'Rule not found or access denied.');
            }
            
            // Delete the rule
            $rule->delete();
            
            return redirect()->back()->with('success', 'Rule deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Error deleting rule: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'An error occurred while deleting the rule: ' . $e->getMessage());
        }
    }
} 