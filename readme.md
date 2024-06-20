
# Commission Calculation Script

## Overview

This script calculates commissions for transactions based on their BIN and the amount of the transaction. The commission rates vary depending on whether the BIN is from an EU country or a non-EU country. The calculations are done in EUR currency.

## Problems in the Original Code

1. **Code Structure and Readability**: 
   - The original code was difficult to read and maintain due to its poor structure and lack of clear separation of concerns.

2. **Deployment and testing issues**: 
   - The BIN list service has a strict rate limit and the exchange rate service requires an API key, adding complexity to the deployment and execution of the script.
   - For these reasons, the original code could not be executed successfully.

3. **Error Handling**: 
   - The original code lacked robust error handling, making it susceptible to runtime errors and unexpected behavior when external services failed or returned unexpected data.

## Refactoring

The refactored version of the script addresses these issues by:

1. **Code Structure**:
   - Separating responsibilities into distinct classes and interfaces, improving readability and maintainability.
   - Utilizing dependency injection to manage dependencies, making the code more modular and easier to test.

2. **Mocking External Services**:
   - To avoid issues with rate limits and API keys, the script uses mock providers for BIN data and exchange rates during development and testing. This approach ensures that the script can be tested reliably without depending on external services.
   - The mock providers return predefined data that simulates the responses from the real services, allowing us to test the script's functionality thoroughly.

3. **Error Handling**:
   - Improved error handling to catch and log exceptions, ensuring that issues are detected and reported without causing the script to crash.

## Usage

### Installation

1. Clone the repository:
   ```sh
   cd /path/to/your/host
   git clone https://github.com/gumione/bwttestcase.git .
   ```

2. Install dependencies using Composer:
   ```sh
   composer install
   ```

### Running the Script

To run the script, use the following command:

```sh
php index.php input.txt
```

### Directory Structure

- `src/`: Contains the source code.
  - `interfaces/`: Contains interface definitions.
  - `providers/`: Contains provider implementations.
  - `utils/`: Contains utility classes.
- `tests/`: Contains unit tests.

### Configuration

The script uses a configuration file (\`config.php\`) to specify the URLs for the BIN list and exchange rate services. You can adjust these settings as needed.
There is an API key, but this is MADE ON PURPOSE, for testing scenario, this mustn't be done in real repo

### Testing

To run the tests, use the following command:

```sh
vendor/bin/phpunit tests
```

### Example Input

The input file should contain transactions in JSON format, one per line:

```json
{"bin":"45717360","amount":"100.00","currency":"EUR"}
{"bin":"516793","amount":"50.00","currency":"USD"}
{"bin":"45417360","amount":"10000.00","currency":"JPY"}
{"bin":"41417360","amount":"130.00","currency":"USD"}
{"bin":"4745030","amount":"2000.00","currency":"GBP"}
```

### Output

The script will output the calculated commissions for each transaction, one per line, for example:

```
1.00
0.47
58.88 // actually, there is gonna be an error, because BIN data is incorrect
2.42  // same here
2.37
```

### Future Improvements

The current implementation uses mock providers for demonstration and testing purposes. For production use, you should replace these mock providers with real implementations that make API calls to the external services. The code is structured to make this replacement straightforward.