<footer>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div>
                    <a class="navbar-brand" href="#">Job Board</a>
                </div>
                <div>
                    <i class="fa fa-facebook-official fa-lg" aria-hidden="true"></i>&nbsp;
                    <i class="fa fa-twitter fa-lg" aria-hidden="true"></i>&nbsp;
                    <i class="fa fa-instagram fa-lg" aria-hidden="true"></i>&nbsp;
                    <i class="fa fa-youtube-play fa-lg" aria-hidden="true"></i>&nbsp;
                    <i class="fa fa-google-plus-official fa-lg" aria-hidden="true"></i>


                </div>
                <div class="copyright">Job Board © 2018 </div>
            </div>
            <div class="col-5">
                <h5>NEWSLETTER</h5>
                <p>Keep up with our always upcoming news and updates. Enter your e-mail and subscribe to our newsletter.</p>
                <form class="form-inline">
                    <label class="sr-only" for="inlineFormInputGroupUsername2">Username</label>
                    <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" id="inlineFormInputGroupUsername2" placeholder="email">
                    </div>

                    <span class="input-group mb-2 mr-sm-2">
                        <button type="submit" class="btn btn-primary ">Subscribe</button>
                    </span>

                </form>
            </div>
            <div class="col">
                <h5>CITIES</h5>
                <ul class="list list-unstyled list-inline-primary">
                    <li class="text-primary"><a href="#">New York</a></li>
                    <li class="text-primary"><a href="#">San Diego</a></li>
                    <li class="text-primary"><a href="#">Los Angeles</a></li>
                    <li class="text-primary"><a href="#">Boston</a></li>
                    <li class="text-primary"><a href="#">Washington</a></li>
                    <li class="text-primary"><a href="#">Chicago</a></li>
                </ul>

            </div>
             <?php
            $sqlBusinessFooter = "SELECT id, business_stream_name,font_icon FROM bussiness_stream WHERE is_removed='0' AND is_active='1' ";
            $resultBusinessFooter = mysqli_query($db,$sqlBusinessFooter);
            $countBusinessFooter = mysqli_num_rows($resultBusinessFooter);
            if($countBusinessFooter>0){
            
            ?>

            <div class="col">
                <h5>CATEGORIES</h5>
                <ul class="list list-unstyled list-inline-primary">
                    <?php     
                    while($contentBusinessFooter = mysqli_fetch_array($resultBusinessFooter,MYSQLI_ASSOC)){
                    
?>
                    <li class="text-primary"><a href="#"><?php echo $contentBusinessFooter["business_stream_name"]?></a></li>
                    <?php }//close while?>
                 </ul>
            </div>
            <?php }//close if?>

        </div>

    </div>
</footer>
