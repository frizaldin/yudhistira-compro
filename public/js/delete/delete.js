const element = document.currentScript.getAttribute('element');

const confirmation = (id) => {
    Swal.fire({
        title: 'Anda yakin?',
        html: "<p style='margin-bottom: 0px;'>Data yang sudah dihapus tidak bisa dikembalikan!</p><small>Data pada yang berkaitan kemungkinan besar akan ikut terhapus.</small>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteProduct(id)
        }
    })
}

const deleteProduct = (id) => {
    showLoading()
    $.ajax({
        url: $(`.deleteForm-${id}`).attr('action'),
        type: 'POST',
        data: { id },
        success: function (e) {
            Swal.close()
            if (e.success) {
                if (element === 'table') {
                    $(`#action-${id}`).html('<span class="badge bg-danger">Data berhasil di hapus! <br />Refresh halaman untuk data terbaru</span>')
                    $(`#data-${id}`).css('background', '#dee2e6')
                } else {
                    $(`#data-${id}`).remove()
                    if (e.folder == 'all') {
                        $("div[id*=data]").remove();
                    }
                }
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: e.message,
                    icon: 'error',
                    showConfirmButton: false,
                    timer: (e.success == true) ? 1500 : 10000
                });
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
    return false
}
