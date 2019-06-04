var gulp = require('gulp'),
    watch = require('gulp-watch'),
    cleanCSS = require('gulp-clean-css'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    insert = require('gulp-insert'),
    babel = require('gulp-babel'),
    uglify = require('gulp-uglify'),
    replace = require('gulp-replace'),
    sass = require('gulp-sass'),
    imagemin = require('gulp-imagemin'),
    browserSync = require('browser-sync').create(),
    pipeline = require('readable-stream').pipeline;

// Gulp Sass Compiler
sass.compiler = require('node-sass');
gulp.task('sass', function () {
    return gulp.src([
        './src/sass/app.scss'
    ])
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(['./dist/css/']))
        .pipe(browserSync.stream());
});

//Gulp Script Concat
gulp.task('script', function () {
    return gulp.src([
        './src/js/lib/input-numeric/jquery.numeric.js',
        './src/js/lib/bootstrap-filestyle/bootstrap-filestyle.js',
        './src/js/app.js',
    ])
        .pipe(concat('app.min.js'))
        //.pipe(insert.prepend('jQuery(document).ready(function ($) {'))
        //.pipe(insert.append('});'))
        .pipe(gulp.dest('./dist/js/'))
        .pipe(babel({presets: ['@babel/env']}))
        .pipe(replace("\\n", ''))
        .pipe(replace("\\t", ''))
        .pipe(replace("  ", ''))
        .pipe(uglify())
        .pipe(gulp.dest('./dist/js/'))
        .pipe(browserSync.stream());
});

// Gulp Image Min
gulp.task('image', () =>
    gulp.src('src/images/*')
        .pipe(imagemin([
            imagemin.optipng({optimizationLevel: 2}),
        ]))
        .pipe(gulp.dest('dist/images'))
);

// Gulp Watch
gulp.task('watch', function () {
    browserSync.init({
        proxy: "http://localhost/ikcofest.ir",
        online: true,
        injectChanges: true
    });

    gulp.watch('src/js/*.js', gulp.series('script'));
    gulp.watch('src/sass/**/*.scss', gulp.series('sass'));
    gulp.watch('./**/*.php').on('change', browserSync.reload);
});

// global Task
gulp.task('default', gulp.parallel('sass', 'script'));