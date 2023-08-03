<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->string("state",2)->nullable();
            $table->string("designation_full",30)->nullable();
            $table->string("name");
            $table->date('last_file_date')->nullable();
            $table->date('first_f1_date')->nullable();
            $table->string("organization_type_full",150)->nullable();
            $table->json("sponsor_candidate_list")->nullable();
            $table->string("party",3)->nullable();
            $table->string("party_full",70)->nullable();
            $table->string("designation",1)->nullable();
            $table->string("organization_type",1)->nullable();
            $table->string("affiliated_committee_name")->nullable();
            $table->string("committee_type_full",60)->nullable();
            $table->date("first_file_date")->nullable();
            $table->string("committee_type",1)->nullable();
            $table->string("treasurer_name",150)->nullable();
            $table->string("filing_frequency",1)->nullable();
            $table->string("committee_id")->unique();
            $table->json("sponsor_candidate_ids")->nullable();
            $table->json("candidate_ids")->nullable();
            $table->date("last_f1_date")->nullable();
            $table->json("cycles")->nullable();
            $table->integer("page")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
