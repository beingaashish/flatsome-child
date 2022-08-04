/**
 * Gulp workflow for Myronjs.
 *
 * Contains the gulp commands to run repetitive tasks.
 */

const gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    sass = require('gulp-sass')(require('node-sass')),
    uglify = require('gulp-uglify'),
    notify = require('gulp-notify'),
    zip = require('gulp-zip'),
    rename = require('gulp-rename'),
    lec = require("gulp-line-ending-corrector");

// Project information.
const info = {
    name: 'FlatsomeChild',
    slug: 'flatsome-child',
};

// Project assets paths.
const paths = {
    scss: {
        src: ["./assets/sass/*.scss", "./assets/sass/**/*.scss"],
        dest: "./",
    },
    prefixStyles: {
        src: "./*.css",
        dest: "./",
    },
	adminScss: {
		src: ["./assets/admin/css/sass/*.scss"],
		dest: "./assets/admin/css/",
	},
	adminPrefixStyles: {
		src: ["./assets/admin/css/*.css"],
		dest: "./assets/admin/css/",
	},
    js: {
        src: ["./assets/js/*.js", "!./assets/js/*.min.js"],
        dest: "./assets/js/",
    },
    zip: {
        src: [
            '**',
            '!vendor',
            '!vendor/**',
            '!node_modules',
            '!node_modules/**',
            '!**/*.scss',
            '!assets/sass',
            '!inc/admin/assets/sass',
            '!inc/admin/sass/**',
            '!inc/customizer/core/assets/scss',
            '!assets/sass/**',
            '!automate',
            '!automate/**',
            '!dest.xml',
            '!dist',
            '!dist/**',
            '!*.json',
            '!*.md',
            '!gulpfile.js',
            '!composer.lock',
            '!phpcs.xml',
            '!.gitlab',
            '!.gitlab/**',
            '!.github',
            '!.github/**'
        ],
        dest: './dist'
    },
}

const styles = gulp.series(compileSass, prefixStyles);
const build  = gulp.series(styles, minifyJs);
const adminStyles = gulp.series(compileAdminSass, adminPrefixStyles);

// Compile SCSS into CSS.
function compileSass() {
    return gulp
        .src(paths.scss.src)
        .pipe(
            sass({
                indentType: "tab",
                indentWidth: 1,
                outputStyle: "expanded",
            })
        )
        .pipe(lec({ verbose: true, eolc: "LF", encoding: "utf8" }))
        .pipe(gulp.dest(paths.scss.dest))
        .on("error", notify.onError());
}

// Prefixes CSS.
function prefixStyles() {
    return gulp
        .src(paths.prefixStyles.src)
        .pipe(
            autoprefixer({
                overrideBrowserslist: ["last 2 versions"],
                cascade: false,
            })
        )
        .pipe(gulp.dest(paths.prefixStyles.dest))
        .on("error", notify.onError());
}

// Compile Admin SCSS into CSS.
function compileAdminSass() {
	return gulp
		.src(paths.adminScss.src)
		.pipe(
			sass({
				indentType: "tab",
				indentWidth: 1,
				outputStyle: "expanded",
			})
		)
		.pipe(lec({ verbose: true, eolc: "LF", encoding: "utf8" }))
		.pipe(gulp.dest(paths.adminScss.dest))
		.on("error", notify.onError());
}

// Prefixes Admin CSS.
function adminPrefixStyles() {
	return gulp
		.src(paths.adminPrefixStyles.src)
		.pipe(
			autoprefixer({
				overrideBrowserslist: ["last 2 versions"],
				cascade: false,
			})
		)
		.pipe(gulp.dest(paths.adminPrefixStyles.dest))
		.on("error", notify.onError());
}

// Minify the js files.
function minifyJs() {
    return gulp
        .src(paths.js.src)
        .pipe(uglify())
        .pipe(rename({ suffix: ".min" }))
        .pipe(gulp.dest(paths.js.dest))
        .on("error", notify.onError());
}

// Watch for file changes.
function watch() {
    gulp.watch(paths.scss.src, styles);
}

// Watch Admin Scss for file changes.
function watchAdminScss() {
	gulp.watch(paths.adminScss.src, adminStyles);
}

// Compress theme into a zip file.
function compressZip() {
    return gulp
        .src(paths.zip.src)
        .pipe(rename(function (path) {
            path.dirname = info.slug + '/' + path.dirname;
        }))
        .pipe(zip(info.slug + '.zip'))
        .pipe(gulp.dest(paths.zip.dest))
        .on('error', notify.onError())
        .pipe(
            notify({
                message: 'Great! Package is ready',
                title: 'Build successful'
            })
        );
}

exports.watch       = watch;
exports.minifyJs    = minifyJs;
exports.build       = build;
exports.compressZip = compressZip;
exports.compileAdminSass = compileAdminSass;
exports.adminPrefixStyles = adminPrefixStyles;
exports.watchAdminScss = watchAdminScss;
