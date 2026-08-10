<?php

class HomeController
{
    public function index()
    {
        $articleModel = new Article();
        $categoryModel = new Category();
        $articles = $articleModel->latest(3);
        $categories = $categoryModel->all();

        view('home', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }
}