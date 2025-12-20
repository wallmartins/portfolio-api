<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('experience_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('experience_id');
            $table->string('locale', 5);
            $table->string('position');
            $table->text('description');
            $table->timestamps();

            $table->foreign('experience_id')
                ->references('id')
                ->on('experiences')
                ->onDelete('cascade');

            $table->unique(['experience_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences_translation');
    }
};
