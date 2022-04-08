<div class="sidebar">
    <div class="entry-widget">
        <div class="widget-profile">
            <div class="info">
                <h3 class="name">Romans Vasiljevs</h3>
                <p class="details">Romans Vasiljevs responsible, purposeful, self-confident, adapt to all changes and situations. Always motivated to learn something new and provide my skills in a professional way. For now, I am working as a Fullstack Developer, as a main developer and holding few projects under control, actively working with Angular / Laravel / Clickhouse and Phalcon fraemwork. During free time learning MERN stack.</p>
            </div>
        </div>
    </div>

    <div class="entry-widget">
        <h5 class="widget-title">Category</h5>
        <ul class="archivee">
            @foreach($category as $cat)
                <li>
                    <a href="{{ route('blog.post_by_category',[$cat->id,str_replace(' ','-',strtolower($cat->category))]) }}"><i class="ico-keyboard_arrow_right"></i> {{ $cat->category }}</a>
                </li>
            @endforeach

        </ul>
    </div>


    <div class="entry-widget">
        <h5 class="widget-title">Meta</h5>
        <ul class="meta-list">
            <li>
                <a href="{{ route('get_login') }}"><i class="ico-keyboard_arrow_right"></i> Log In</a>
            </li>
            <li>
                <a href="{{ route('get_register') }}"><i class="ico-keyboard_arrow_right"></i> Register</a>
            </li>

        </ul>
    </div>
</div>