<script>
    var navbarStyle = localStorage.getItem("navbarStyle");
    if (navbarStyle && navbarStyle !== 'transparent') {
        document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
    }
</script>
<div class="d-flex align-items-center">
    <div class="toggle-icon-wrapper">
        <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span
                    class="toggle-line"></span></span></button>

    </div>
    <a class="navbar-brand" href="index.html">
        <div class="d-flex align-items-center py-3"><img class="me-2"
                                                         src="assets/img/icons/spot-illustrations/falcon.png"
                                                         alt="" width="40"/><span class="font-sans-serif">falcon</span>
        </div>
    </a>
</div>
