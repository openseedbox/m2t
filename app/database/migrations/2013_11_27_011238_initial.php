<?php

use Illuminate\Database\Migrations\Migration;

class Initial extends Migration {

	public function up() {
		Schema::create("torrents", function($table) {
			$table->increments("id");
			$table->timestamps();

			$table->string("hash", 40);
			$table->string("name", 300);
			$table->integer("total_size_bytes")->unsigned()->default(0);
			$table->text("base64_metadata")->nullable();
			$table->string("magnet_uri", 1000)->nullable();
			$table->boolean("in_transmission")->default(false);

			$table->unique("hash");
		});

		Schema::create("files", function($table) {
			$table->increments("id");

			$table->string("name", 200);
			$table->string("full_location", 1000);
			$table->integer("length_bytes");
			$table->integer("torrent_id")->unsigned();

			$table->foreign("torrent_id")->references("id")->on("torrents")->onDelete("cascade")->onUpdate("cascade");
		});

		Schema::create("trackers", function($table) {
			$table->increments("id");

			$table->integer("torrent_id")->unsigned();
			$table->string("tracker_url", 500);
			$table->integer("seeds")->default(0);
			$table->integer("leechers")->default(0);
			$table->integer("completed")->default(0);

			$table->foreign("torrent_id")->references("id")->on("torrents")->onDelete("cascade")->onUpdate("cascade");
		});
	}

	public function down() {
		Schema::dropIfExists("trackers");
		Schema::dropIfExists("files");
		Schema::dropIfExists("torrents");
	}

}