<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBlogComentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_coments', function (Blueprint $table) {
            $table->id();
            $table->string('iduser', 15)->nullable();
            $table->text('text');
            $table->unsignedBigInteger('blog_id');
            $table->timestamp('date')->useCurrent();
            
            $table->foreign('blog_id')->references('id')->on('blog')->onDelete('cascade');
            
            $table->index('blog_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_coments');
    }
};
