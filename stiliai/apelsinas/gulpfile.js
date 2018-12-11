var browserSync = require('browser-sync');
var gulp = require("gulp");
var gulpAutoprefixer = require('gulp-autoprefixer');
var gulpCleanCss = require('gulp-clean-css');
var gulpLess = require('gulp-less');
var gulpRename = require('gulp-rename');
var gulpUglify = require('gulp-uglify');
var gulpUtil = require("gulp-util");
var webpack = require("webpack");
//
gulp.task('browser-sync', function() {
    browserSync({
        server: false,
        proxy: "mightmedia.test"
    });
});

// gulp.task('watch', ['browser-sync'], function() {
gulp.task('watch', function() {
    gulp.watch('./assets/less/**/**/**/**/*.less', gulp.series('css'));
});

gulp.task('css', function() {
    return gulp.src('./assets/less/*.less')
        .pipe(gulpLess())
        .on('error', function(err) {
            gulpUtil.log(err.message);
        })
        .pipe(gulpAutoprefixer({browsers: ['last 2 versions']}))
        // .pipe(gulpCleanCss())
        // .pipe(gulpRename({
        //     suffix: '.min'
        // }))
        .pipe(gulp.dest('./assets/css/'))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('build', gulp.series('css'));

gulp.task('default', gulp.series('css', gulp.parallel('watch', 'browser-sync')));