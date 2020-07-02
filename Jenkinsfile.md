## Jenkinsfile

To add continuous integration with Jenkins to your project, rename `Jenkinsfile.rename.me` to  `Jenkinsfile` and start developing your `Jenkinsfile`. More information at https://github.dxc.com/platform-dxc/jenkins

## Jenkins shared libraries

A Jenkins shared library is way to mutualize some piece of Jenkins pipeline accross a team or accross the company. 
A library can contain:

- Custom pipeline steps
- Groovy functions used to implement features
- Custom pipeline (yes you perfectly understand you can define a pipeline and share it with the company)

More information in the [Jenkins documentation for shared libraries](https://jenkins.io/doc/book/pipeline/shared-libraries/).

## Where to find it in DXC

The DevOps Enablement team which also administrates Jenkins seeded a library with reusable components.
You will find the code [here](https://github.dxc.com/lgil3/devops-jenkins-sharedlibs).

As this library is still in alpha version it was not moved to the GitHub Platform DXC organization. However you can start to contribute
to improve it and make it more stable.
