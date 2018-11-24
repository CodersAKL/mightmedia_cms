var webpack = require('webpack');

module.exports = {
    entry: {
        'public': [
            'assets/js/src/script.js',
        ]
    },
    output: {
        path: __dirname + '/assets/js/',
        filename: '[name].bundle.js',
        chunkFilename: '[id].bundle.js'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: "babel-loader",
                query: {
                    compact: false // because I want readable output
                }
            }
        ]
    }
};