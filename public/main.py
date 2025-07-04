import pymysql
import requests
from datetime import datetime, timedelta

# Konfigurasi koneksi MySQL
db = pymysql.connect(
    host="host_db_anda",       # contoh: "sql.freedb.tech"
    user="username_anda",
    password="password_anda",
    database="nama_db_anda"
)

# Token & Chat ID Telegram
TOKEN = "7974899938:AAGDlu3G5aKA1tA3eaCHuc4BsA83e5N35lo"
CHAT_ID = "1586262535"  # Bisa diganti sesuai masing-masing pemilik

today = datetime.today().date()

try:
    with db.cursor() as cursor:
        cursor.execute("SELECT * FROM kendaraan")
        hasil = cursor.fetchall()

        for row in hasil:
            id, nama, nomor_hp, nama_kendaraan, plat_nomor, tanggal_kir = row
            tanggal_kir = datetime.strptime(str(tanggal_kir), "%Y-%m-%d").date()
            kir_berikutnya = tanggal_kir + timedelta(days=180)

            if kir_berikutnya <= today:
                pesan = f"""🚨 Halo {nama},
KIR untuk kendaraan *{nama_kendaraan}* ({plat_nomor}) sudah jatuh tempo.
Mohon segera diperiksa.

Terima kasih."""
                url = f"https://api.telegram.org/bot{TOKEN}/sendMessage"
                params = {
                    "chat_id": CHAT_ID,
                    "text": pesan,
                    "parse_mode": "Markdown"
                }
                try:
                    response = requests.get(url, params=params)
                    print(f"Notifikasi dikirim ke {nama}: {response.status_code}")
                except Exception as e:
                    print(f"Gagal kirim pesan ke Telegram: {e}")
finally:
    db.close()
