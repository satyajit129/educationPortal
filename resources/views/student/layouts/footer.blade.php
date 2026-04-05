<footer class="footer mt-auto">


    <div class="copyright bg-white">
        <p>
            <span id="copy-year"></span> {{ $copyright ??'' }}
        </p>
    </div>

    <script>
        var d = new Date();
        var year = d.getFullYear();
        document.getElementById("copy-year").innerHTML = year;
    </script>
</footer>
