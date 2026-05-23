        <?php
// includes/admin_footer.php — Layout footer untuk panel admin
// File ini mengakhiri dokumen HTML dan menutup koneksi database jika diperlukan
?>
            </main>
        </div>

        <script>
            // Auto-dismiss flash messages after 4 seconds
            document.querySelectorAll('.flash-message').forEach(el => {
                setTimeout(() => {
                    el.style.transition = 'opacity 0.5s';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }, 4000);
            });
        </script>
    </body>
</html>
