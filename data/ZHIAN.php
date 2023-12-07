<?php

function getTopLabel()
{
    $mycon = new MyConnect();
    $result = $mycon->inquire('blog_type', array("*"), null);
    echo json_encode($result);
    $mycon->close();
}
