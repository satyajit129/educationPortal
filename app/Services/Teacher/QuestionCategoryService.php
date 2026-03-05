<?php

namespace App\Services\Teacher;

use App\Models\QuestionCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class QuestionCategoryService
{
    public function renderQuestionCategoryList()
    {
        $categories = QuestionCategory::with('parent')->latest()->paginate(20);
        return view('teacher.pages.question_category_list', compact('categories'));
    }

    public function renderQuestionCategoryForm($id = null)
    {
        $category = $id ? QuestionCategory::findOrFail($id) : null;
        $parents = QuestionCategory::where('status', '1')->get();
        return view('teacher.pages.question_category_form', compact('category', 'parents'));
    }

    public function handleQuestionCategorySave($request, $id = null)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'parent_category_id' => 'nullable|exists:question_categories,id',
                'status' => 'required|in:1,0',
            ]);

            $category = QuestionCategory::updateOrCreate(
                ['id' => $id],
                [
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'description' => $request->description,
                    'status' => $request->status ?? 1,
                    'parent_category_id' => $request->parent_category_id,
                    'created_by' => Auth::id(),
                ]
            );

            // 🔹 Session Logic
            if ($id) {
                // Edit mode → remove previous session
                session()->forget('last_parent_category_id');
            } else {
                // Create mode → store last selected parent
                session(['last_parent_category_id' => $request->parent_category_id]);
            }

            return redirect()
                ->route('questionCategoryList')
                ->with('success', 'Category saved successfully.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', $th->getMessage())
                ->withInput();
        }
    }

    public function handleQuestionCategoryDelete($id)
    {
        $category = QuestionCategory::findOrFail($id);
        $category->delete();
        return redirect()->route('questionCategoryList')->with('success', 'Category deleted successfully.');
    }
}
