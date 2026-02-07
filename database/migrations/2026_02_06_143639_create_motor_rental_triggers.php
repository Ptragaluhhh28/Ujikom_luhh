<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trigger to update motor status to 'disewa' when booking is accepted (status 'aktif')
        DB::unprepared("
            CREATE TRIGGER after_booking_update
            AFTER UPDATE ON penyewaan
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'aktif' AND OLD.status != 'aktif' THEN
                    UPDATE motor SET status = 'disewa' WHERE id = NEW.motor_id;
                ELSEIF NEW.status = 'selesai' AND OLD.status != 'selesai' THEN
                    UPDATE motor SET status = 'tersedia' WHERE id = NEW.motor_id;
                ELSEIF NEW.status = 'batal' AND OLD.status != 'batal' THEN
                    UPDATE motor SET status = 'tersedia' WHERE id = NEW.motor_id;
                END IF;
            END
        ");

        // Trigger to record revenue sharing after payment success
        DB::unprepared("
            CREATE TRIGGER after_payment_success
            AFTER UPDATE ON transaksi
            FOR EACH ROW
            BEGIN
                DECLARE v_admin_share DECIMAL(12,2);
                DECLARE v_owner_share DECIMAL(12,2);
                
                IF NEW.status = 'success' AND OLD.status != 'success' THEN
                    -- Assuming 20% for admin, 80% for owner
                    SET v_admin_share = NEW.jumlah * 0.2;
                    SET v_owner_share = NEW.jumlah * 0.8;
                    
                    INSERT INTO bagi_hasil (penyewaan_id, bagi_hasil_pemilik, bagi_hasil_admin, tanggal, created_at, updated_at)
                    VALUES (NEW.penyewaan_id, v_owner_share, v_admin_share, NOW(), NOW(), NOW());
                    
                    -- Also mark booking as aktif if it was pending
                    UPDATE penyewaan SET status = 'aktif' WHERE id = NEW.penyewaan_id AND status = 'pending';
                END IF;
            END
        ");

        // Function to calculate price (optional but requested 'use trigger/procedure/function')
        DB::unprepared("
            CREATE FUNCTION calculate_rental_price(p_motor_id INT, p_durasi_tipe VARCHAR(20), p_durasi_val INT)
            RETURNS DECIMAL(12,2)
            DETERMINISTIC
            BEGIN
                DECLARE v_price DECIMAL(12,2);
                DECLARE v_harian DECIMAL(12,2);
                DECLARE v_mingguan DECIMAL(12,2);
                DECLARE v_bulanan DECIMAL(12,2);
                
                SELECT tarif_harian, tarif_mingguan, tarif_bulanan INTO v_harian, v_mingguan, v_bulanan
                FROM tarif_rental WHERE motor_id = p_motor_id LIMIT 1;
                
                IF p_durasi_tipe = 'harian' THEN
                    SET v_price = v_harian * p_durasi_val;
                ELSEIF p_durasi_tipe = 'mingguan' THEN
                    SET v_price = v_mingguan * p_durasi_val;
                ELSEIF p_durasi_tipe = 'bulanan' THEN
                    SET v_price = v_bulanan * p_durasi_val;
                ELSE
                    SET v_price = 0;
                END IF;
                
                RETURN v_price;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS after_booking_update");
        DB::unprepared("DROP TRIGGER IF EXISTS after_payment_success");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_rental_price");
    }
};
