# API Tutorial Video - Contoh Postman

**Base URL:** `http://localhost/yudhistira-compro/system/public/api/v1`

Tidak perlu auth (public).

---

## 1. List Tutorial Video

**Method:** `GET`  
**URL:** `{{base_url}}/tutorial-videos`

### Query Params (opsional)

| Key       | Value | Keterangan                    |
|-----------|--------|-------------------------------|
| `search`  | cara mengajar | Cari di title/judul        |
| `per_page`| 10     | Jumlah per halaman (1-100), default 15 |
| `page`    | 1      | Halaman (untuk pagination)    |

### Contoh Request

```
GET http://localhost/yudhistira-compro/system/public/api/v1/tutorial-videos
GET http://localhost/yudhistira-compro/system/public/api/v1/tutorial-videos?per_page=5
GET http://localhost/yudhistira-compro/system/public/api/v1/tutorial-videos?search=tata
```

### Contoh Response (200 OK)

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "How to Teach Effectively",
      "judul": "Cara Mengajar yang Efektif",
      "file": "http://localhost/yudhistira-compro/system/public/storage/upload/tutorial-video/video_1234567890_1234.mp4",
      "thumbnail": "http://localhost/yudhistira-compro/system/public/storage/upload/tutorial-video/123456_tutorial-video.webp",
      "sort_order": 0,
      "created_at": "2026-02-13T10:00:00.000000Z"
    },
    {
      "id": 2,
      "title": "Classroom Management",
      "judul": "Manajemen Kelas",
      "file": "http://localhost/yudhistira-compro/system/public/storage/upload/tutorial-video/video_1234567891_5678.mp4",
      "thumbnail": "http://localhost/yudhistira-compro/system/public/storage/upload/tutorial-video/123457_tutorial-video.webp",
      "sort_order": 1,
      "created_at": "2026-02-13T11:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 2
  }
}
```

### Response jika belum ada data

```json
{
  "success": true,
  "data": [],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 0
  }
}
```

---

## 2. Detail Tutorial Video (by ID)

**Method:** `GET`  
**URL:** `{{base_url}}/tutorial-videos/{id}`

### Contoh Request

```
GET http://localhost/yudhistira-compro/system/public/api/v1/tutorial-videos/1
```

### Contoh Response (200 OK)

```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "How to Teach Effectively",
    "judul": "Cara Mengajar yang Efektif",
    "file": "http://localhost/yudhistira-compro/system/public/storage/upload/tutorial-video/video_1234567890_1234.mp4",
    "thumbnail": "http://localhost/yudhistira-compro/system/public/storage/upload/tutorial-video/123456_tutorial-video.webp",
    "sort_order": 0,
    "created_at": "2026-02-13T10:00:00.000000Z"
  }
}
```

### Response jika ID tidak ditemukan (404)

```json
{
  "code": 404,
  "success": false,
  "message": "Tutorial video tidak ditemukan."
}
```

---

## Setup di Postman

1. Buat environment (opsional):  
   - Variable `base_url` = `http://localhost/yudhistira-compro/system/public/api/v1`

2. Request **List:**
   - Method: GET  
   - URL: `{{base_url}}/tutorial-videos`  
   - Params: tambah `per_page`, `search`, `page` jika perlu

3. Request **Detail:**
   - Method: GET  
   - URL: `{{base_url}}/tutorial-videos/1` (ganti 1 dengan id yang ada)

4. Tidak perlu header Authorization (endpoint public).
