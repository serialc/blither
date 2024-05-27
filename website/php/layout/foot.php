<div id="footer" class="container-fluid footer bg-dark-subtle text-body-secondary mt-5">
    <div class="container">
        <footer class="pt-4">
            <div class="row">
                <div id="footer_left" class="col-md-4 mt-3">
                </div>

                <div id="footer_middle" class="col-md-4 mt-3 text-center">
                </div>

                <div id="footer_right" class="col-md-4 mt-3 text-end">
                </div>

                <div class="col-12 mt-5 text-center" style="font-size: 0.7em">
                    <p>&copy; blither 
<?php

$today = new \DateTime(date('Y-m-d'));
echo $today->format('Y');

?>
                    </p>

                </div>
            </div>
        </footer>
    </div>
</div>
<script src="/js/blither.js"></script>
</body>
</html>
