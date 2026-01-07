if (typeof ignoreConfirmation === 'undefined') {
    var ignoreConfirmation = document.currentScript.getAttribute('ignoreConfirmation');
}

var $editors = $(".ckeditor");
if ($editors.length) {
    for (instance in ClassicEditor.instances) {
        ClassicEditor.instances[instance].updateElement();
    }
}

// Update CKEditor 4 instances (untuk textarea dengan class summernote)
if (typeof CKEDITOR !== 'undefined') {
    for (var instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
}

$('._form').submit(function (e) {
    e.preventDefault()

    // Update CKEditor 4 instances sebelum submit
    if (typeof CKEDITOR !== 'undefined') {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    }

    const run = (e) => {
        showLoading()

        $.ajax({
            url: this.action,
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            success: function (e) {
                console.log(e);
                Swal.close()
                Swal.fire({
                    title: (e.success == true) ? 'Berhasil!' : 'Gagal!',
                    text: e.message,
                    icon: (e.success == true) ? 'success' : 'error',
                    showConfirmButton: false,
                    timer: (e.success == true) ? 1500 : 10000
                });
                if (e.url) {
                    window.location.replace(e.url)
                }
                if (e.reload) {
                    window.location.reload()
                }
            },
            error: function (xhr, status, error) {
                Swal.close()
                var err = eval("(" + xhr.responseText + ")");
                Swal.fire({
                    title: 'Gagal!',
                    text: err.message,
                    icon: 'error',
                    buttons: {
                        cancel: "Tutup",
                    },
                });
            }
        });
        return false;
    };

    if (ignoreConfirmation) {
        run(e)
    } else {
        Swal.fire({
            title: 'Apakah data anda sudah benar?',
            text: "Pastikan data yang anda masukan sudah benar untuk di simpan",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, data sudah benar',
            cancelButtonText: 'Tidak, saya akan cek lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                run(e)
            }
        })
    }

})
