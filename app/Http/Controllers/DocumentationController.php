<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    /**
     * Display the documentation home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('documentation.index');
    }
    
    /**
     * Display the student calendar API documentation.
     *
     * @return \Illuminate\View\View
     */
    public function studentCalendar()
    {
        return view('documentation.api.student_calendar');
    }
} 