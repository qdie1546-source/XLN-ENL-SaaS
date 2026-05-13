</main>
</div><!-- /main wrapper -->
</div><!-- /flex container -->

<script>
function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var icon = document.getElementById('sidebar-toggle-icon');
    var brandText = document.getElementById('brand-text');
    var toggleText = document.getElementById('sidebar-toggle-text');
    var navSpans = sidebar.querySelectorAll('nav span, .border-t span');
    var sectionTitle = sidebar.querySelector('.border-t .px-3');

    if (sidebar.classList.contains('w-64')) {
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-16');
        icon.classList.remove('rotate-180');
        brandText.classList.add('hidden');
        toggleText.textContent = '展开';
        sectionTitle.classList.add('hidden');
        navSpans.forEach(function(span) { span.classList.add('hidden'); });
    } else {
        sidebar.classList.remove('w-16');
        sidebar.classList.add('w-64');
        icon.classList.add('rotate-180');
        brandText.classList.remove('hidden');
        toggleText.textContent = '收起';
        sectionTitle.classList.remove('hidden');
        navSpans.forEach(function(span) { span.classList.remove('hidden'); });
    }
}

function toggleDark() {
    var html = document.documentElement;
    var isDark = html.classList.toggle('dark');
    localStorage.setItem('linkhub-dark', isDark);
}
</script>

</body>
</html>
