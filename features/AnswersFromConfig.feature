Feature:

  Scenario: Generate certificates by answering questions
    Given I'm in a project root directory
    When I execute command sslgen -vv --config=example/sslgen.yaml --directory=example/test
    And when asked to "Choose schema" I answer "1"
    Then displayed log message should contain:
      """
      Using schema [caSchema].
      Output directory set to
      Saving certificate as file [ca.pem].
      Saving private key as file [privkey.pem].
      """
    When I execute command:
      """
      sslgen -vv -s -o \
        --config=example/sslgen.yaml \
        --directory=example/test \
        --schema=2 \
        --caCert=example/test/ca.pem \
        --caKey=example/test/privkey.pem
      """
    Then displayed log message should contain:
      """
      Using schema [serverSchema].
      Output directory set to
      Saving certificate signing request (CSR) as file [server.req].
      Saving certificate as file [server.pem].
      Saving private key as file [server.key].
      """
    When I execute command:
      """
      sslgen -vv -s -o \
        --config=example/sslgen.yaml \
        --directory=example/test \
        --schema=clientSchema \
        --caCert=example/test/ca.pem \
        --caKey=example/test/privkey.pem
      """
    Then displayed log message should contain:
      """
      Using schema [clientSchema].
      Output directory set to
      Saving certificate signing request (CSR) as file [client.req].
      Saving certificate as file [client.pem].
      Saving private key as file [client.key].
      """
    Then I remove the example/test directory
