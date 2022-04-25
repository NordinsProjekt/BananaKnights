<?php


function contactForm()
{
    $text = <<<XYZ
        <div class="container" style='margin-top: 100px'>
        <header class="text-center mb-5">
        <h1 class="display-4">Contact</h1>
        </header>
        <div class="row">
            <div class="col-lg-6">
            <form action="" class="contact-form">
                <div class="row">
                <div class="form-group col-lg-6">
                    <label for="firstName">Firstname *</label>
                    <input id="firstName" type="text" name="firstname" placeholder="Enter your firstname" class="form-control" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>
                <div class="form-group col-lg-6">
                    <label for="lastName">Lastname *</label>
                    <input id="lastName" type="text" name="lastname" placeholder="Enter your lastname" class="form-control">
                </div>
                <div class="form-group col-lg-12">
                    <label for="email">Email *</label>
                    <input id="email" type="email" name="email" placeholder="Enter your email" class="form-control">
                </div>
                <div class="form-group col-lg-12">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" placeholder="Enter your message" rows="4" class="form-control"></textarea>
                </div>
                <div class="form-group col-lg-12">
                    <button type="submit" class="btn btn-outline-primary w-100">Send message</button>
                </div>
                </div>
            </form>
            </div>
            <div class="col-lg-6">
            <p class="font-italic text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
            molestiae quas vel sint commodi repudiandae consequuntur voluptatum laborum
            numquam blanditiis harum quisquam eius sed odit fugiat iusto fuga praesentium
            optio, eaque rerum! Provident similique accusantium nemo autem.</p>
            <p class="font-italic text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor 
            incididunt ut labore et dolore magna aliqua.</p>
            <ul class="mb-0 list-inline text-center">
                <li class="list-inline-item"><a href="#" class="social-link social-link-facebook"><i class="fab fa-facebook-f"></i></a></li>
                <li class="list-inline-item"><a href="#" class="social-link social-link-twitter"><i class="fab fa-twitter"></i></a></li>
                <li class="list-inline-item"><a href="#" class="social-link social-link-google-plus"><i class="fab fa-google-plus-g"></i></a></li>
                <li class="list-inline-item"><a href="#" class="social-link social-link-instagram"><i class="fab fa-instagram"></i></a></li>
                <li class="list-inline-item"><a href="#" class="social-link social-link-email"><i class="fas fa-envelope"></i></a></li>
            </ul>
            </div>
        </div>
        </div>

        <footer class="" style="margin-top: 70px; height: 2rem">
        <div class="container text-center">
            <p class="font-italic text-muted mb-0">&copy; Copyrights Coolbooks.com All rights reserved.</p>
        </div>
        </footer> 
    XYZ;

    return $text;
}

?>
