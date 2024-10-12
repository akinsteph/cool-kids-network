const { src, dest, watch } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cleanCSS = require('gulp-clean-css');

const path = require('path');

const PATHS = {
	scss: './assets/scss/style.scss',
	dist: './',
};

function compileStyles() {
	return src(PATHS.scss)
		.pipe(sass().on('error', sass.logError))
		.pipe(postcss([autoprefixer()]))
		// .pipe(cleanCSS())
		.pipe(dest(PATHS.dist));
}

function watchStyles() {
	watch('./assets/styles/scss/**/*.scss', compileStyles);
}

exports.style = compileStyles;
exports.watch = watchStyles;
