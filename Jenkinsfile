pipeline {
    agent any

    stages {
        stage('echo') {
            steps {
               echo 'Clone OM'
            }
        }
        stage('git pull') {
            steps {
               bat 'git pull main'
            }
        }
    }
}
