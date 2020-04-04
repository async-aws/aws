---
category: contribute
---

# Documentation

The documentation is hosted on [async-aws.com](https://async-aws.com/). The contents
are automatically updated from the contents of the [async-aws/aws](https://github.com/async-aws/aws)
repository.

The [build process](#the-process-of-building-the-website) is fairly automated and
doesn't require much thought. But it is interesting to understand how one actually
would improve the website.

## Writing documentation

Writing documentation to the website in not more complex than it normally is. Making
changes to the Markdown files in `./docs` is everything one need to do.

If a new file is created and it should be in the website's sidebar menu, then remember
to update `./couscous.yml`.

Here are some guidelines that are helpful when writing good documentation:

- Write `AsyncAws` or `async-aws` to refer to the project.
- Don't use "we" at any cost.
- Try not to write "you".
- Define all variables to make examples executable.
- Use meaningful names of variables and values. Avoid "foobar" and other dummy values.
- Write examples with  use `use` statements and don't use `<?php`.
- Use input objects over arrays.
- Use short variable names for clients. Ie `$lambda` over `$lambdaClient` and `$s3` over `$s3Client`.

## Improving the website's appearance

To update the websites HTML or style one need to use Couscous. Its basic operations
are found in their [Getting started guide](http://couscous.io/docs/getting-started.html).
You do also need NPM installed to build the assets.

There is a Makefile script to run `npm install` and `encore prod`. It will be executed
with `make website-assets`. That script will automatically run before Couscous is
generating the HTML.

What you basically need to do is to open two terminals. In one you run

```shell
couscous preview
```

And to make sure the assets are updating when you making a change to the asset source:

```shell
cd website
./node_modules/.bin/encore dev --watch
```

Now you are good to go.

## The process of building the website

So here is an overview how the automated build process works every time there is
a push to master:

### 1. Generate the HTML

The first step is to use [Couscous](http://couscous.io/) to convert all Markdown files
in `./docs` to HTML files. Couscous then looks at the Twig template in `./website/template`
to get the basic HTML layout. All markdown files will use the `default.twig` template
unless other is specified at the metadata section at the top of the markdown file.

The sidebar menu structure is defined in `./couscous.yml`.

All frontend assets are built using Webpack Encore. The source files lives in
`./website/assets`.

### 2. Process HTML files

After the HTML files are generated, Couscous will execute `make website-post-process`.
That will look at the generated HTML and make some changes to it. As example it will
style all code examples with `highlight.js`.

### 3. Publishing the files

There is a github action that make sure to generate the HTML and push it do a
"read only"-repository for the website. That repository is using [Netlify](https://www.netlify.com/)
to deploy the contents to [async-aws/aws](https://github.com/async-aws/aws).
