	$('.slider').slick({
		fade:true,//�؂�ւ����t�F�[�h�ōs���B�����l��false�B
		autoplay: true,//�����I�ɓ����o�����B�����l��false�B
		autoplaySpeed: 3000,//���̃X���C�h�ɐ؂�ւ��҂�����
		speed:1000,//�X���C�h�̓����̃X�s�[�h�B�����l��300�B
		infinite: true,//�X���C�h�����[�v�����邩�ǂ����B�����l��true�B
		slidesToShow: 1,//�X���C�h����ʂ�3��������
		slidesToScroll: 1,//1��̃X�N���[����3���̎ʐ^���ړ����Č�����
		arrows: true,//���E�̖�󂠂�
		prevArrow: '<div class="slick-prev"></div>',//��󕔕�Preview��HTML��ύX
		nextArrow: '<div class="slick-next"></div>',//��󕔕�Next��HTML��ύX
		dots: true,//�����h�b�g�i�r�Q�[�V�����̕\��
        pauseOnFocus: false,//�t�H�[�J�X�ňꎞ��~�𖳌�
        pauseOnHover: false,//�}�E�X�z�o�[�ňꎞ��~�𖳌�
        pauseOnDotsHover: false,//�h�b�g�i�r�Q�[�V�������}�E�X�z�o�[�ňꎞ��~�𖳌�
});

//�X�}�z�p�F�X���C�_�[���^�b�`���Ă��~�߂��ɃX���C�h�����������ꍇ
$('.slider').on('touchmove', function(event, slick, currentSlide, nextSlide){
    $('.slider').slick('slickPlay');
});