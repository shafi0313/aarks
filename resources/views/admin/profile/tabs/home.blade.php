<div class="row">
    <div class="col-xs-12 col-sm-3 center">
        <span class="profile-picture">
            <img class="editable img-responsive" alt="Alex's Avatar" id="avatar2"
                src="assets/images/avatars/profile-pic.jpg">
        </span>

        <div class="space space-4"></div>

        <a href="#" class="btn btn-sm btn-block btn-success">
            <i class="ace-icon fa fa-plus-circle bigger-120"></i>
            <span class="bigger-110">Add as a friend</span>
        </a>

        <a href="#" class="btn btn-sm btn-block btn-primary">
            <i class="ace-icon fa fa-envelope-o bigger-110"></i>
            <span class="bigger-110">Send a message</span>
        </a>
    </div><!-- /.col -->

    <div class="col-xs-12 col-sm-9">
        <h4 class="blue">
            <span class="middle">{{$user->name}}</span>

            <span class="label label-purple arrowed-in-right">
                <i class="ace-icon fa fa-circle smaller-80 align-middle"></i>
                online
            </span>
        </h4>

        <div class="profile-user-info">
            <div class="profile-info-row">
                <div class="profile-info-name"> Username </div>

                <div class="profile-info-value">
                    <span>{{$user->name}}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Location </div>

                <div class="profile-info-value">
                    <i class="fa fa-map-marker light-orange bigger-110"></i>
                    <span>Netherlands</span>
                    <span>Amsterdam</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Age </div>

                <div class="profile-info-value">
                    <span>38</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Joined </div>

                <div class="profile-info-value">
                    <span>{{$user->created_at->format('d/m/Y')}}</span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Last Online </div>

                <div class="profile-info-value">
                    <span>{{now()->format('d/m/Y')}}</span>
                </div>
            </div>
        </div>

        <div class="hr hr-8 dotted"></div>

        <div class="profile-user-info">
            <div class="profile-info-row">
                <div class="profile-info-name"> Website </div>

                <div class="profile-info-value">
                    <a href="#" target="_blank">{{url('/')}}</a>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">
                    <i class="middle ace-icon fa fa-facebook-square bigger-150 blue"></i>
                </div>

                <div class="profile-info-value">
                    <a href="#aarks">Find me on Facebook</a>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name">
                    <i class="middle ace-icon fa fa-twitter-square bigger-150 light-blue"></i>
                </div>

                <div class="profile-info-value">
                    <a href="#aarks">Follow me on Twitter</a>
                </div>
            </div>
        </div>
    </div><!-- /.col -->
</div><!-- /.row -->
