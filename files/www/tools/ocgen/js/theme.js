// Membuat instance Vue
new Vue({
    el: '#app',
    data: {
        selectedTheme: ''
    },
    mounted() {
        this.loadThemeFromCookie();
    },
    methods: {
        // Fungsi untuk memuat tema dari cookie saat halaman dimuat
        loadThemeFromCookie() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'inc/theme.php', true);
            xhr.onreadystatechange = () => {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    this.selectedTheme = response.theme;
                }
            };
            xhr.send();
        },
        // Fungsi untuk menyimpan tema
        saveTheme() {
            var selectedTheme = this.selectedTheme;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'inc/theme.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = () => {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Sukses',
                            text: 'Tema berhasil disimpan',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Refresh halaman
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Gagal menyimpan tema',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            };
            xhr.send('theme=' + selectedTheme);
        }
    }
});
