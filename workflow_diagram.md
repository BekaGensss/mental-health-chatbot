graph TD
    subgraph Publik (Murid)
        A[Akses Homepage /] --> B{Klik Icon Chatbot};
        B --> C[JS: Pop-up Muncul];
        C --> D{Isi Data Diri};
        D -- Lanjut --> E[Chat Dialog Aktif (NLP)];
        E -- Ketik 'MULAI FORM' --> F[Backend PHP Deteksi Intent];
        F --> G[JS: Tampilkan Form Skoring];
        G --> H[Isi Kuesioner PHQ/GAD/KPK/DASS-21];
        H --> I[Submit Data (Skoring & Simpan DB)];
        I --> J[Halaman Sukses & Visualisasi Skor];
        J --> K[Opsi: Export PDF Laporan];
    end

    subgraph Admin (Guru BK)
        L[Akses /dashboard (Login)] --> M{Pilih Menu Report};
        M -- Hasil Jawaban --> N[Statistik & Diagram Klasifikasi];
        M -- Data Pengisian --> O[Tabel Data Mentah];
        N --> P[Opsi: Export Data CSV];
        O --> Q[Lihat Detail Jawaban Murid];
        M -- Kelola Pertanyaan --> R[CRUD Pertanyaan Master];
    end