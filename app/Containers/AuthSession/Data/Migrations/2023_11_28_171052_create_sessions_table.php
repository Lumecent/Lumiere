<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create( 'auth_sessions', function ( Blueprint $table ) {
            $table->id()->unsigned();

            $table->nullableMorphs( 'model' );

            $table->string( 'token' )->index();
            $table->string( 'ip' )->nullable();
            $table->string( 'user_agent' )->nullable();

            $table->timestamps();
            $table->dateTime( 'expired_at' );
        } );
    }

    public function down(): void
    {
        Schema::dropIfExists( 'auth_sessions' );
    }
};
