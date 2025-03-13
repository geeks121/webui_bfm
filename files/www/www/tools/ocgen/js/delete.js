new Vue({
  el: '#app',
  data() {
    return {
      outputs: [], // Array to store command outputs
      confirmDelete: false
    };
  },
  methods: {
    deleteOcgen() {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete Ocgen!'
      }).then((result) => {
        if (result.isConfirmed) {
          this.getDeleteOcgen();
        }
      });
    },
    getDeleteOcgen() {
      axios.post('api.php', {
        action: 'delete_ocgen'
      })
        .then(response => {
          console.log(response.data);
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Ocgen has been deleted!',
            showConfirmButton: false,
            timer: 1500
          });
        })
        .catch(error => {
          console.error(error);
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Failed to delete Ocgen!',
            showConfirmButton: false,
            timer: 1500
          });
        });
    }
  }
});
