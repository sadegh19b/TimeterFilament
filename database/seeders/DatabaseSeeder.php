<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'  => 'صادق',
            'email' => 'sadegh19b@gmail.com',
            'password' => \Hash::make('sadegh')
        ]);

        Project::create([
            'slug' => 'brand-mart',
            'title' => 'برندمارت | BrandMart',
            'logo' => 'projects/logo/BrandMart-Logo_1699434322.svg',
            'link' => 'https://dev.web.brand-mart.com',
            'jira_link' => 'https://brandmart.atlassian.net/jira/software/c/projects/BRM/boards/5',
        ]);
    }
}
