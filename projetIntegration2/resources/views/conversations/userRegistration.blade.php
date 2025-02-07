<form action="" method="POST">
    {{ csrf_field() }}
    <input type="text" name="name" placeholder="Name">
    <input type="submit" value="Submit">

</form>