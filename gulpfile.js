var gulp = require('gulp'),
    notify  = require('gulp-notify'),
    phpunit = require('gulp-phpunit');

gulp.task('phpunit', function() {
    var options = {debug: false, notify: true};
    gulp.src('app/tests/*.php')
        .pipe(phpunit('', options))
        .on('error', notify.onError({
            title: "Failed Tests!",
            message: "Error(s) occurred during testing..."
        }));
});

gulp.task('default', function(){
    gulp.watch('app/**/*.php', { debounceDelay: 2000 }, ['phpunit']);
});