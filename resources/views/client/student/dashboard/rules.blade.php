@extends("client.student.layouts.master")
@section("title", "Rules & Regulations")
@section("content")
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
<h2 class="text-xl font-semibold text-gray-800 mb-4">School Rules & Regulations</h2>
@if(isset($ruleCategories) && count($ruleCategories) > 0)
<div class="space-y-4">
@foreach($ruleCategories as $category)
<div class="border rounded-lg overflow-hidden mb-4"><div class="bg-gray-50 p-4"><h3 class="font-medium">{{ $category->name }}</h3></div>
<div class="p-4">@if($category->description)<p class="mb-3">{{ $category->description }}</p>@endif
@if(isset($category->rules) && count($category->rules) > 0)<ul class="list-disc pl-5 space-y-2">@foreach($category->rules as $rule)<li><strong>{{ $rule->title }}</strong>@if($rule->description)<p>{{ $rule->description }}</p>@endif</li>@endforeach</ul>@else<p>No rules in this category.</p>@endif</div></div>@endforeach</div>@else<p>No rules and regulations have been added by your school yet.</p>@endif</div>@endsection
