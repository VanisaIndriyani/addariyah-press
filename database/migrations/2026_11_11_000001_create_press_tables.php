<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author');
            $table->string('publisher')->default('Addariyah Press');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('pages')->nullable();
            $table->string('isbn')->nullable();
            $table->string('qrcbn')->nullable();
            $table->string('editor')->nullable();
            $table->string('book_layout')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('for_sale')->default(false);
            $table->boolean('upcoming')->default(false);
            $table->string('expected_label')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('ebook_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'for_sale']);
            $table->index(['is_active', 'upcoming']);
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->text('body');
            $table->string('image_path')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::create('manuscripts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('message')->nullable();
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::create('ecatalogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('caption')->nullable();
            $table->string('image_path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('note')->nullable();
            $table->decimal('total', 12, 2);
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->nullable()->constrained()->nullOnDelete();
            $table->string('book_title');
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('qty');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('ecatalogs');
        Schema::dropIfExists('manuscripts');
        Schema::dropIfExists('downloads');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('books');
        Schema::dropIfExists('settings');
    }
};
