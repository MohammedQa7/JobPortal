<?php

namespace Database\Seeders;

use App\Helpers\GlobalHelper;
use App\Models\Category;
use App\Models\Project;
use App\Models\Specialty;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Project::factory(200)->create();

        // $categories = [
        //     "Development and Programming",
        //     "Design and Creative Arts",
        //     "Writing and Content Creation",
        //     "Marketing and Advertising",
        //     "Data, IT, and Technology",
        //     "Audio and Video Production",
        //     "Business and Finance",
        //     "Customer and Administrative Support",
        //     "Language and Localization",
        //     "Education and Tutoring",
        //     "Event and Lifestyle Services"
        // ];
        // foreach ($categories as $category) {
        //     Category::create([
        //         'name' => $category,
        //         'slug' => GlobalHelper::slug_ar($category),
        //     ]);
        // }

        $specialties = [
            "Web Development ",
            "Mobile App Development ",
            "Graphic Design",
            "UX/UI Design",
            "Content Writing and Copywriting",
            "SEO ",
            "Social Media Marketing",
            "Video Editing",
            "Animation (2D and 3D)",
            "WordPress Development",
            "eCommerce Development ",
            "Data Analysis",
            "Cybersecurity",
            "Software Development",
            "Machine Learning and Artificial Intelligence",
            "Virtual Assistance",
            "Digital Marketing",
            "Voice Over",
            "Photography and Photo Editing",
            "Business Consulting",
            "Cloud Computing ",
            "Blockchain Development",
            "Game Development ",
            "Email Marketing",
            "Motion Graphics",
            "Logo Design",
            "API Integration",
            "DevOps",
            "Financial Analysis",
            "Accounting and Bookkeeping",
            "Podcast Editing",
            "Music Composition and Production",
            "CAD Design",
            "Product Design",
            "Course Creation",
            "Resume Writing and Career Coaching",
            "Event Planning and Management",
            "Test Preparation Tutoring",
            "Translation and Localization",
            "Customer Support and Chat Support",
            "Legal Consulting and Contract Drafting"
        ];
        foreach ($specialties as $specialty) {
            Specialty::create([
                'name' => $specialty,
                'slug' => GlobalHelper::slug_ar($specialty),
            ]);
        }



    }
}