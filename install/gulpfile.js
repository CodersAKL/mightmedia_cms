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
        proxy: "mightmedia.test/install"
    });
});
//
// gulp.task('browser-sync-reload', ['webpack'], function () {
//     browserSync.reload();
// });

// gulp.task('watch', ['browser-sync'], function() {
gulp.task('watch', function() {
    gulp.watch('./assets/less/**/**/**/**/*.less', gulp.series('css'));
    gulp.watch('./assets/js/src/**/**/**/*.js', gulp.series('webpack'));
    // gulp.watch('./js/*.js', ['browser-sync-reload']);
    gulp.watch('./assets/js/**/**/**/*.js');
});

gulp.task('webpack', function (callback) {
    webpack(require('./webpack.config.js'), function (err, stats) {
        if (err) throw new gulpUtil.PluginError("webpack", err);
        gulpUtil.log("[webpack]", stats.toString({}));
        callback();
    });
});

gulp.task('js-compress', function() {
    return gulp.src('./assets/js/*.bundle.js')
        .on('error', function(err) {
            gulpUtil.log(err.message);
        })
        .pipe(gulpRename({
            suffix: '.min'
        }))
        .pipe(gulpUglify())
        .pipe(gulp.dest('./assets/js/'));
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

gulp.task('build', gulp.series('css', 'webpack'));

gulp.task('default', gulp.series('css', 'webpack', gulp.parallel('watch', 'browser-sync')));