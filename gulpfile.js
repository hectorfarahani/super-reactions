var version = '1.3.0';
var versioningFiles = [
  'super-reactions.php',
  'constants.php',
  'readme.txt'
];

var srcs = [
  './**',
  '!./node_modules/**',
  '!./.*',
  '!*.json',
  '!*.xml',
  '!*.md',
  '!*.lock',
  '!gulpfile.js',
  '!*.yml',
  '!svn-assets/**',
  '!admin/assets/src/**',
  '!front/assets/src/**'
];

var gulp = require('gulp');
var wpPot = require('gulp-wp-pot');
var clean = require('gulp-clean');
var sass = require('gulp-sass');
var save = require('gulp-save');
var rename = require('gulp-rename');
var cleanCSS = require('gulp-clean-css');
var uglify = require('gulp-uglify');
var babel = require('gulp-babel');
var replace = require('gulp-replace');
var touch = require('gulp-touch-fd');
var browserSync = require('browser-sync').create();

// Generate pot
gulp.task(
  'generatePot',
  function () {
    var files = [
      '*.php',
      'admin/**/*.php',
      'front/**/*.php',
      'includes/**/*.php',
    ];

    return gulp.src(files)
      .pipe(
        wpPot(
          {
            domain: 'super-reactions',
            destFile: 'super-reactions.pot',
            package: 'WP_Reactions',
            lastTranslator: 'Hector Farhan <hi@farahani.dev>',
            team: 'Hector Farhan <hi@farahani.dev>'
          }
        )
      )
      .pipe(gulp.dest('languages/super-reactions.pot'))
  }
);

// Clean
gulp.task(
  'clean',
  function () {
    return gulp.src(
      [
        'admin/assets/dist/*',
        'front/assets/dist/*',
        'languages/*',
      ]
    )
      .pipe(
        clean(
          {
            read: false,
            force: true
          }
        )
      );
  }
)

// Sass compile
gulp.task(
  'css',
  function (cb) {
    gulp.src(
      [
        'admin/assets/src/scss/admin.scss',
      ]
    )
      .pipe(sass({ outputStyle: 'expanded' }).on('error', sass.logError))
      .pipe(save('before-dest'))
      .pipe(rename({ basename: 'srea-admin', dirname: '' }))
      .pipe(gulp.dest('admin/assets/dist/css'))
      .pipe(browserSync.stream({match: '**/*.css'}))
      .pipe(cleanCSS())
      .pipe(rename({ basename: 'srea-admin', suffix: '.min', dirname: '' }))
      .pipe(gulp.dest('admin/assets/dist/css'))
      .pipe(browserSync.stream({match: '**/*.css'}))

    gulp.src(
      [
        'front/assets/src/scss/front.scss',
      ]
    )
      .pipe(sass({ outputStyle: 'expanded' }).on('error', sass.logError))
      .pipe(save('before-dest'))
      .pipe(rename({ basename: 'srea-front', dirname: '' }))
      .pipe(gulp.dest('front/assets/dist/css'))
      .pipe(cleanCSS())
      .pipe(browserSync.stream({match: '**/*.css'}))
      .pipe(rename({ basename: 'srea-front', suffix: '.min', dirname: '' }))
      .pipe(gulp.dest('front/assets/dist/css'))
      .pipe(browserSync.stream({match: '**/*.css'}))
    cb();
  }
);

// JS
gulp.task(
  'js',
  function (cb) {
    gulp.src(
      [
        'admin/assets/src/js/**/*.js',
      ]
    )
      .pipe(babel({
        presets: ['@babel/env']
      }))
      .pipe(rename({ basename: 'srea-admin', dirname: '' }))
      .pipe(gulp.dest('admin/assets/dist/js'))
      .pipe(uglify())
      .pipe(rename({ basename: 'srea-admin', suffix: '.min', dirname: '' }))
      .pipe(gulp.dest('admin/assets/dist/js'));

    gulp.src(
      [
        'front/assets/src/js/**/*.js',
      ]
    )
      .pipe(babel({
        presets: ['@babel/env']
      }))
      .pipe(rename({ basename: 'srea-front', dirname: '' }))
      .pipe(gulp.dest('front/assets/dist/js'))
      .pipe(uglify())
      .pipe(rename({ basename: 'srea-front', suffix: '.min', dirname: '' }))
      .pipe(gulp.dest('front/assets/dist/js'));

    cb();
  }
);

// Set version
gulp.task(
  'version',
  function (cb) {
    gulp.src(versioningFiles)
      .pipe(
        replace(
          /(\* Version:.+\s|SREA_VERSION.*|Stable tag:.*\s)(\d+\.\d+\.\d+)/g,
          function (match, p1, p2) {
            if (p2 === version) {
              console.log('The current version is ' + version + '. No changes.')
              return match;
            }
            console.log('\x1b[31m' + 'Version changed from ' + p2 + ' to ' + version);
            return match.replace(p2, version);
          }
        )
      )
      .pipe(
        gulp.dest(
          function (file) {
            return file.base;
          }
        )
      )
      .pipe(touch());
    cb();
  }
);

// Reload task
gulp.task('stream', function (cb) {
  browserSync.reload({ stream: true })
  cb();
});

gulp.task('reload', function (cb) {
  browserSync.reload()
  cb();
});

// Watch Task
gulp.task('watch', function (cb) {
  // Init browserSync
  browserSync.init({
    injectChanges: true,
    proxy: '74.wordpress.test/'
  })
  gulp.watch(['./front/**/*.scss', './admin/**/*.scss'], gulp.series(['css']))
  gulp.watch(['./front/**/*.js', './admin/**/*.js'], gulp.series(['js']))
  gulp.watch(
    [
      './admin/**/*.php',
      './front/**/*.php',
      './includes/**/*.php'
    ], gulp.parallel(['reload']))
  cb();
});

// Copy task
gulp.task('copy', function () {
  return gulp.src(srcs)
    .pipe(gulp.dest('/home/hector/svn-releases/super-reactions/trunk'))
})

gulp.task('default', gulp.series('clean', 'version', 'generatePot', 'css', 'js'));
