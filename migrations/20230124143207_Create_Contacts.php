<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager;

/**
 * Класс создание таблицы пользователей
 */
class CreateContacts extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Manager::schema()->create('Contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->integer('contact_id');
            $table->text('name');
            $table->string('email');
            $table->integer('delete');
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Manager::schema()->drop('Users');
    }
}
