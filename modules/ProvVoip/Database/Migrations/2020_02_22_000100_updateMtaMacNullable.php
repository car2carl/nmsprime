<?php

use Illuminate\Database\Schema\Blueprint;

class UpdateMtaMacNullable extends BaseMigration
{
    protected $tablename = 'mta';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tablename, function (Blueprint $table) {
            $table->string('mac')->sizeof(17)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tablename, function (Blueprint $table) {
            $table->string('mac')->sizeof(17)->change();
        });
    }
}
