
    // $("#clickme").click(function() {
    //     Swal.fire({
    //         position: 'top-end',
    //         icon: 'success',
    //         title: 'Success',
    //         showConfirmButton: false,
    //         timer: 1500
    //     })
    // });



    function displayStatus(status, message) {
        if (status=="success"){
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 5000
            })
        }else if (status=="failure"){
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 5000
            })
        }
    }