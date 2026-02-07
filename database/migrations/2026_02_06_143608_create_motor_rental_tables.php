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
        Schema::create('motor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemilik_id')->constrained('users')->onDelete('cascade');
            $table->string('merk');
            $table->integer('tipe_cc');
            $table->string('no_plat')->unique();
            $table->enum('status', ['tersedia', 'disewa', 'perawatan', 'pending'])->default('pending');
            $table->string('photo')->nullable();
            $table->string('dokumen_kepemilikan')->nullable();
            $table->timestamps();
        });

        Schema::create('tarif_rental', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motor_id')->constrained('motor')->onDelete('cascade');
            $table->decimal('tarif_harian', 12, 2);
            $table->decimal('tarif_mingguan', 12, 2);
            $table->decimal('tarif_bulanan', 12, 2);
            $table->timestamps();
        });

        Schema::create('penyewaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('motor_id')->constrained('motor')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesali');
            $table->enum('tipe_durasi', ['harian', 'mingguan', 'bulanan']);
            $table->decimal('harga', 12, 2);
            $table->enum('status', ['pending', 'aktif', 'selesai', 'batal'])->default('pending');
            $table->timestamps();
        });

        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaan')->onDelete('cascade');
            $table->decimal('jumlah', 12, 2);
            $table->string('metode_pembayaran');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamp('tanggal')->useCurrent();
            $table->timestamps();
        });

        Schema::create('bagi_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaan')->onDelete('cascade');
            $table->decimal('bagi_hasil_pemilik', 12, 2);
            $table->decimal('bagi_hasil_admin', 12, 2);
            $table->timestamp('settled_at')->nullable();
            $table->timestamp('tanggal')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bagi_hasil');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('penyewaan');
        Schema::dropIfExists('tarif_rental');
        Schema::dropIfExists('motor');
    }
};
