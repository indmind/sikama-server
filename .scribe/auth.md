# Authenticating requests

Authenticate requests to this API's endpoints by sending an **`Authorization`** header with the value **`"Bearer {token}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

You can retrieve the token by calling Google SignIn endpoint with **debug_token** as access_token value.
