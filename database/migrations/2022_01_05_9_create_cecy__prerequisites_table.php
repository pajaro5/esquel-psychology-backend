<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCecyPrerequisitesTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION_CECY'))->create('prerequisites', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('pre_requisito_id')
                ->comment('Id del curso que es prerequisito')
                ->constrained('cecy.courses');

            $table->foreignId('course_id')
                ->nullable()
                ->comment('Id del curso al que pertenece prerequisito académico')
                ->constrained('cecy.courses');
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION_CECY'))->dropIfExists('prerequisites');
    }
}
