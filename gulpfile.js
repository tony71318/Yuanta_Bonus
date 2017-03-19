var gulp = require('gulp'),
	uglify = require('gulp-uglify'),	// uglify 用來把js的程式碼變成一行
	//plumber = require('gulp-plumber');	// 用來在uglify編譯不過時 保持gulp運作
	livereload = require('gulp-livereload'),
	imagemin = require('gulp-imagemin');


function errorLog(error) {
	console.error.bind(console);	//	plumber的替代方案
	this.emit('end');	
}

// Scripts Task
// Uglifies
gulp.task('scripts',function () {
	gulp.src('js/*.js')	// src = source; * = 全選
		// pipe 表示要執行的動作
		//.pipe(plumber())	//必須放在最前面
		.pipe(uglify())
		.on('error', errorLog)	//	plumber的替代方案
		.pipe(gulp.dest('minjs')) // dest = output's destination
		.pipe(livereload());
})

// Css Task
// livereload
gulp.task('css', function(){
	gulp.src('css/*.css')
		.pipe(livereload());
});

// Html Task
// livereload
gulp.task('html', function(){
	gulp.src('./*.html')
		.pipe(livereload());
});

// php Task
// livereload
gulp.task('php', function(){
	gulp.src('./*.php')
		.pipe(livereload());
	gulp.src('php/*/*.php')
		.pipe(livereload());
});

// Images Task
// compress image
gulp.task('images', function(){
	gulp.src('images/*')
		.pipe(imagemin())
		.pipe(gulp.dest('images/compressed_images'))
});


// Watch Task
// Watched JS
gulp.task('watch', ['scripts','css'], function(){
	livereload.listen();﻿	// 放在 watch 的前面

	gulp.watch('js/*.js', ['scripts']);
	gulp.watch('css/*.css', ['css']);
	gulp.watch('./*.html', ['html']);
	gulp.watch('./*.php', ['php']);
});

gulp.task('default', ['scripts','css','html','php','watch']);