<?

if (isset($_SESSION['USER_ID_RIGHTS_DENIED'])) {
    $USER->Authorize($_SESSION['USER_ID_RIGHTS_DENIED']);
    unset($_SESSION['USER_ID_RIGHTS_DENIED']);
}

?>
        </div>
    </div>
</body>
</html>
