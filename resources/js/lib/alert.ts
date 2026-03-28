import Swal from 'sweetalert2';

export function notifySuccess(message: string) {
    Swal.fire({
        icon: 'success',
        title: 'Sukses!',
        text: message,
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
    })
}

export function notifyError(message: string) {
    Swal.fire({
        icon: 'error',
        title: 'Gabim!',
        text: message,
        timer: 2500,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
    })
}

export function confirmDelete(message = 'A jeni të sigurt që doni ta fshini?') {
    return Swal.fire({
        title: 'Konfirmim',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Po, fshije!',
        cancelButtonText: 'Anulo',
    })
}
