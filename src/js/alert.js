
function showAlert(statusCadastro) {
    if (statusCadastro === "success") {
        Swal.fire({
            title: "Sucesso!",
            text: "Usuário cadastrado com sucesso!",
            icon: "success",
            timer: 3000,
            showConfirmButton: false
        });
    } else if (statusCadastro === "error") {
        Swal.fire({
            title: "Erro!",
            text: "Falha ao cadastrar usuário.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        Swal.fire({
            title: "Atenção",
            text: statusCadastro,
            icon: "warning",
            timer: 3000,
            showConfirmButton: false
        });
    }
}

function showNotLoginAlert() {
    Swal.fire({
        title: 'Acesso Negado',
        text: 'Você precisa estar logado para acessar esta página.',
        icon: 'warning',
        confirmButtonText: 'Fazer Login',
        confirmButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../views/login.php';
        }
    });
}


function showLoginError(errorMessage) {
    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: errorMessage,
            confirmButtonColor: '#28a745',
        });
    }
}


function categoryError() {

    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 não está carregado!');
        return;
    }


    const urlParams = new URLSearchParams(window.location.search);
    const successMessage = urlParams.get('success');
    const errorMessage = urlParams.get('error');

    if (successMessage) {

        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: decodeURIComponent(successMessage),
            confirmButtonText: 'Ok'
        });
    }

    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: decodeURIComponent(errorMessage),
            confirmButtonText: 'Ok'
        });
    }
}
