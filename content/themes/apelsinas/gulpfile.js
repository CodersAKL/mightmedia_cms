var browserSync = require('browser-sync');
var gulp = require("gulp");
var gulpAutoprefixer = require('gulp-autoprefixer');
var gulpCleanCss = require('gulp-clean-css');
var sass = require('gulp-sass');
sass.compiler = require('node-sass');

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
    gulp.watch('./assets/scss/**/**/**/**/*.scss', gulp.series('sass'));
});

 
gulp.task('sass', function () {
return gulp.src('./assets/scss/default.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulpAutoprefixer({browsers: ['last 2 versions']}))
    .pipe(gulp.dest('./'))
    .pipe(browserSync.reload({
        stream: true
    }));
});

// gulp.task('css', function() {
//     return gulp.src('./assets/less/*.less')
//         .pipe(gulpLess())
//         .on('error', function(err) {
//             gulpUtil.log(err.message);
//         })
//         .pipe(gulpAutoprefixer({browsers: ['last 2 versions']}))
//         // .pipe(gulpCleanCss())
//         // .pipe(gulpRename({
//         //     suffix: '.min'
//         // }))
//         .pipe(gulp.dest('./assets/css/'))
//         .pipe(browserSync.reload({
//             stream: true
//         }));
// });

gulp.task('build', gulp.series('sass'));

gulp.task('default', gulp.series('sass', gulp.parallel('watch', 'browser-sync')));